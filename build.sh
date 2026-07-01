#!/usr/bin/env bash
# exit on error
set -o errexit

# Jalankan database migration dan seeder secara paksa di server production
php artisan migrate:fresh --seed --force