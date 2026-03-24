#!/bin/bash
set -e

cd /home/ubuntu/localmindv2

IMAGE_TAG=$1
ACTIVE=$(cat .active_env 2>/dev/null || echo "blue")
TARGET=$([ "$ACTIVE" = "blue" ] && echo "green" || echo "blue")

echo "🔗 Ensuring network exist..."
docker network inspect localmind_proxy >/dev/null 2>&1 || docker network create localmind_proxy

echo "🚀 Deploying to project: localmind-$TARGET with tag $IMAGE_TAG"
export IMAGE_TAG=$IMAGE_TAG

docker compose -p localmind-$TARGET -f docker-compose.$TARGET.yml pull
docker compose -p localmind-$TARGET -f docker-compose.$TARGET.yml up -d

echo "⏳ Waiting for $TARGET containers to stabilize..."
sleep 10

echo "🔗 Ensuring Nginx proxy is running..."
if ! docker ps --format '{{.Names}}' | grep -q "^nginx$"; then
    docker compose -f docker-compose.proxy.yml up -d
fi

echo "🩺 Performing health check on frontend_${TARGET}..."
if docker exec frontend_$TARGET wget -q --spider http://localhost:3000; then
    echo "✅ Health OK"

    echo "📦 Running Laravel migrations on backend_$TARGET..."
    docker exec backend_$TARGET php artisan migrate --force

    echo "🔁 Switching Nginx traffic to $TARGET..."
    sed -i "s/set \$env .*/set \$env $TARGET;/" nginx/default.conf
    docker exec nginx nginx -s reload

    docker exec backend_$TARGET php artisan optimize:clear
    docker exec backend_$TARGET php artisan optimize
    
    echo $TARGET > .active_env
    
    echo "🧹 Cleaning up old project: localmind-$ACTIVE..."
    docker compose -p localmind-$ACTIVE -f docker-compose.$ACTIVE.yml down -v --remove-orphans
    docker network prune -f --filter "label=com.docker.compose.project=localmind-$ACTIVE"
    docker image prune -af

    echo "🎉 Blue-Green Deploy successful!"
else
    echo "❌ Health check failed. Rolling back $TARGET..."
    docker compose -p localmind-$TARGET -f docker-compose.$TARGET.yml down
    exit 1
fi