# Установка проекта (MacOS)

1. Установить composer: \
`php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"` \
`php composer-setup.php` \
`php -r "unlink('composer-setup.php');"`
2. Установить phpunit: `php composer.phar require --dev phpunit/phpunit`
3. Проверим установку: `vendor/bin/phpunit` (увидим список доступных опций команды)

# Запуск тестов

1. Запуск тестов: `php vendor/bin/phpunit tests`
