<?php

namespace App\Helper;

class ConstHelper
{
    // Options Tutorial Akses
    private const OPTION_TUTORIAL_AKSES = ['Publik', 'Anggota', 'Khusus', 'Registered'];

    public static function getOptionTutorialAkses(): array
    {
        $options = self::OPTION_TUTORIAL_AKSES;
        sort($options);

        return $options;
    }

    // Options Service
    private const OPTION_SERVICES = ['Database', 'Website', 'Tutorial'];

    public static function getOptionServices(): array
    {
        $options = self::OPTION_SERVICES;
        sort($options);

        return $options;
    }

    // Options Website Target
    private const OPTION_WEBSITE_TARGET = [
        'php',
        'php-laravel',
        'php-laravel-vitejs',
        'java-spring',
        'kotlin-ktor',
        'python-streamlit',
    ];

    public static function getOptionWebsiteTarget(): array
    {
        $options = self::OPTION_WEBSITE_TARGET;
        sort($options);

        return $options;
    }

    // Options Database Target
    private const OPTION_DATABASE_TARGET = [
        'none',
        'pgsql',
        // 'mysql'
    ];

    public static function getOptionDatabaseTarget(): array
    {
        $options = self::OPTION_DATABASE_TARGET;

        return $options;
    }

    // Options Role
    private const OPTION_ROLES = [
        'Delcom',
        'Aplikasi',
        'Anggota',
        'Tutorial',
        'Kursus',
        'Nama Cantik',
    ];

    public static function getOptionRoles(): array
    {
        $options = self::OPTION_ROLES;
        sort($options);

        return $options;
    }

    // Options PHP
    private const OPTION_PHP = [
        'PHP 8.4',
    ];

    public static function getOptionPhp(): array
    {
        $options = self::OPTION_PHP;
        sort($options);

        return $options;
    }

    // Options DNSType
    private const OPTION_DNSTYPES = [
        'A',
        'AAAA',
        'CNAME',
        'ALIAS',
        'MX',
        'TXT',
        'NS',
        'SOA',
        'SRV',
        'CAA',
    ];

    public static function getOptionDNSTypes(): array
    {
        $options = self::OPTION_DNSTYPES;
        sort($options);

        return $options;
    }

    // TipeKontenKursus
    public const TIPE_KONTEN_TITLE = 'title';

    public const TIPE_KONTEN_FREE_FORM = 'free-form';

    public const TIPE_KONTEN_CODE_GRADING = 'code-grading';

    public const TIPE_KONTEN_API_GRADING = 'api-grading';

    public const TIPE_KONTEN_QUIZ_MOBILE = 'quiz-mobile';
}



