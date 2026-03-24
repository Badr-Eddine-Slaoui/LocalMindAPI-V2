#!/bin/bash
set -e

cd /home/ubuntu/localmindv2

IMAGE_TAG=$1
ACTIVE=$(cat .active_env 2>/dev/null || echo "blue")
TARGET=$([ "$ACTIVE" = "blue" ] && echo "green" || echo "blue")

echo "🔗 Ensuring network exist..."
docker network inspect localmind_proxy >/dev/null 2>&1 || docker network create localmind_proxy

echo "🚀 Deploying to project: localmind-$TARGET-v2 with tag $IMAGE_TAG"
export IMAGE_TAG=$IMAGE_TAG

docker compose -p localmind-$TARGET-v2 -f docker-compose.$TARGET.v2.yml pull
docker compose -p localmind-$TARGET-v2 -f docker-compose.$TARGET.v2.yml up -d

echo "⏳ Waiting for $TARGET containers to stabilize..."
sleep 10

echo "🔗 Ensuring Caddy proxy is running..."
if ! docker ps --format '{{.Names}}' | grep -q "^caddy$"; then
    docker compose -f docker-compose.proxy.v2.yml up -d
fi

echo "🩺 Performing health check on frontend_${TARGET}_v2..."
if docker exec frontend_${TARGET}_v2 wget -q --spider http://localhost:3000; then
    if docker exec backend_${TARGET}_v2 wget -q --spider http://localhost:8000; then
        echo "✅ Health OK"

        echo "📦 Running Laravel migrations on backend_${TARGET}_v2..."
        docker exec backend_${TARGET}_v2 php artisan migrate --force

        docker exec backend_${TARGET}_v2 php artisan optimize:clear
        docker exec backend_${TARGET}_v2 php artisan optimize
        
        echo $TARGET > .active_env
        
        echo "🧹 Cleaning up old project: localmind-$ACTIVE-v2..."
        docker compose -p localmind-$ACTIVE-v2 -f docker-compose.$ACTIVE.v2.yml down -v --remove-orphans
        docker network prune -f --filter "label=com.docker.compose.project=localmind-$ACTIVE-v2"
        docker image prune -af

        echo "🎉 Blue-Green Deploy successful!"
    else
        echo "❌ Backend Health check failed. Rolling back $TARGET..."
        exit 1
    fi
else
    echo "❌ Frontend Health check failed. Rolling back $TARGET..."
    exit 1
fi