#!/bin/bash
set -e

cd /home/ubuntu/localmindv2

IMAGE_TAG=$1
ACTIVE=$(cat .active_env 2>/dev/null || echo "")
TARGET=$([ "$ACTIVE" = "blue" ] && echo "green" || echo "blue")

echo "🔗 Ensuring network exists..."
docker network inspect localmind_proxy >/dev/null 2>&1 || docker network create localmind_proxy

echo "🚀 Deploying to $TARGET with tag $IMAGE_TAG"
export IMAGE_TAG=$IMAGE_TAG

docker compose -p localmind-$TARGET-v2 -f docker-compose.$TARGET.v2.yml pull
docker compose -p localmind-$TARGET-v2 -f docker-compose.$TARGET.v2.yml up -d

echo "⏳ Waiting for containers..."
sleep 10

echo "🔗 Ensuring Caddy is running..."
if ! docker ps --format '{{.Names}}' | grep -q "^caddy$"; then
    docker compose -f docker-compose.proxy.v2.yml up -d
fi

echo "🩺 Health check..."
if docker exec frontend_${TARGET}_v2 wget -q --spider http://localhost:3000; then
    if docker exec backend_${TARGET}_v2 curl -f http://localhost:8000/api/health > /dev/null 2>&1; then

        echo "✅ Health OK"

        echo "📦 Running Laravel tasks..."
        docker exec backend_${TARGET}_v2 php artisan migrate --force
        docker exec backend_${TARGET}_v2 php artisan optimize:clear
        docker exec backend_${TARGET}_v2 php artisan optimize

        if [ -z "$ACTIVE" ]; then
            echo "⚡ First deployment detected..."

            docker network disconnect localmind_proxy backend_${TARGET}_v2 || true
            docker network disconnect localmind_proxy frontend_${TARGET}_v2 || true

            docker network connect --alias backend_active localmind_proxy backend_${TARGET}_v2
            docker network connect --alias frontend_active localmind_proxy frontend_${TARGET}_v2

            echo $TARGET > .active_env

            echo "🎉 First deploy ready!"
            exit 0
        fi

        echo "🔁 Switching traffic to $TARGET..."

        if docker ps --format '{{.Names}}' | grep -q "^backend_${ACTIVE}_v2$"; then
            docker network disconnect localmind_proxy backend_${ACTIVE}_v2 || true
            docker network connect localmind_proxy backend_${ACTIVE}_v2 || true
        fi

        if docker ps --format '{{.Names}}' | grep -q "^frontend_${ACTIVE}_v2$"; then
            docker network disconnect localmind_proxy frontend_${ACTIVE}_v2 || true
            docker network connect localmind_proxy frontend_${ACTIVE}_v2 || true
        fi

        docker network disconnect localmind_proxy backend_${TARGET}_v2 || true
        docker network disconnect localmind_proxy frontend_${TARGET}_v2 || true

        docker network connect --alias backend_active localmind_proxy backend_${TARGET}_v2
        docker network connect --alias frontend_active localmind_proxy frontend_${TARGET}_v2

        echo $TARGET > .active_env

        echo "🧹 Cleaning old environment..."
        docker compose -p localmind-$ACTIVE-v2 -f docker-compose.$ACTIVE.v2.yml down -v --remove-orphans

        docker image prune -af

        echo "🎉 Deploy successful!"

    else
        echo "❌ Backend failed"
        exit 1
    fi
else
    echo "❌ Frontend failed"
    exit 1
fi