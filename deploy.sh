#!/bin/bash

# Set environment to production
export APP_ENV=production

# Run migrations
php artisan migrate --force

# Import database data
if [ -f "c:/Apache24/htdocs/realmsz-beackup/files/1.sql" ]; then
    mysql -h realmsz-dev.cfw12vw5qnff.us-east-1.rds.amazonaws.com -u vapor -pBWjStu58PxaqlC3GYVA3pHkUmP18vyaxMSRFkEZp realmsz-dev < c:/Apache24/htdocs/realmsz-beackup/files/1.sql
fi

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize
php artisan optimize 