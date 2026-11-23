# itdel-app-sdi

composer update vendor/package --dry-run

## Syntax

### Membuat Model

php artisan make:model VariabelModel

### Membuat Livewire

php artisan make:livewire Anggota/AnggotaLivewire
php artisan make:livewire Database/DatabaseLivewire
php artisan make:livewire Website/WebsiteLivewire
php artisan make:livewire Command/CommandLivewire
php artisan make:livewire External/GithubLivewire
php artisan make:livewire External/HostingerLivewire
php artisan make:livewire External/HostingerDetailLivewire

## Audit

Composer audit

vendor/bin/phpstan analyse

# Setup konfigurasi livewire
php artisan vendor:publish --tag=livewire:config