#!/bin/bash
set -e

cd /home/ubuntu/localmind

IMAGE_TAG=$1

ACTIVE=$(cat .active_env 2>/dev/null || echo "blue")

if [ "$ACTIVE" = "blue" ]; then
    TARGET="green"
else
    TARGET="blue"
fi

echo "🚀 Deploying to $TARGET with tag $IMAGE_TAG"

export IMAGE_TAG=$IMAGE_TAG

docker compose -f docker-compose.$TARGET.yml pull
docker compose -f docker-compose.$TARGET.yml up -d

echo "⏳ Waiting for app to boot..."
sleep 8

if curl -f http://localhost/api/health; then
    echo "✅ Health OK"

    echo "🔁 Switching traffic to $TARGET"
    sed -i "s/set \$env .*/set \$env $TARGET;/" nginx/default.conf
    docker exec nginx nginx -s reload

    echo $TARGET > .active_env

    echo "📦 Running migrations..."
    docker exec backend_$TARGET php artisan migrate --force

    echo "🧠 Optimizing..."
    docker exec backend_$TARGET php artisan optimize:clear
    docker exec backend_$TARGET php artisan optimize

    echo "🧹 Cleaning old images..."
    docker image prune -af

else
    echo "❌ Health failed. Rolling back."
    docker compose -f docker-compose.$TARGET.yml down -v
    exit 1
fi