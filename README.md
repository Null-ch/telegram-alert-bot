# Telegram Alert Bot

<p align="center">
    <img src="https://github.com/user-attachments/assets/b7396e85-b7df-4e56-984a-3083b57ef107" width="400">
</p>

<br>

Этот репозиторий содержит код для Telegram бота или нескольких ботов, предназначенных для сбора оповещений о сообщениях в группах и личных чатах с последующей записью в базу данных для статистики обращений.

## Особенности

- **Мониторинг в режиме реального времени:** Отслеживает новые сообщения в группах Telegram и приватных чатах.
- **Ведение журнала базы данных:** Записывает все соответствующие взаимодействия пользователей в базу данных для углубленного анализа и составления отчетов.
- **Отслеживание пользователей:** Создает записи об авторах сообщений, что позволяет анализировать их в зависимости от конкретного пользователя.
- **Отслеживание уникального взаимодействия:** Отслеживает уникальные сообщения от пользователя в течение настраиваемого периода времени, чтобы избежать записи повторяющихся диалогов.
- **Настраиваемые тайм-ауты:** Установите тайм-аут (`TIMEOUT_TO_NEXT_MESSAGE`), чтобы определить, как долго нужно ждать, прежде чем рассматривать новое сообщение от того же пользователя как уникальное событие. При установке значения "0" будет записываться каждое сообщение от пользователей.
- **Список игнорирования:** Игнорирует сообщения от определенных пользователей на основе их идентификатора Telegram.
- **Панель администратора:** Содержит полнофункциональную панель администрирования, созданную с помощью [MoonShine 3.x](https://moonshine-laravel.com/)
- **Раздел рассылок:** Позволяет создать рассылку, по закрепленным за аккаунтом бота чатам (Ваши обращения копятся в базе и помечаются тегом аккаунта, который используется при инициализации бота).
- **История всех сообщений:** Реализован сбор и хранение всех сообщений поступающих в бота, за исключением списка из игнор листа
- **Отчеты по обращениям:** Реализован функционал генирации, сохранения и скачивания отчетов по фильтру дат (от и до)
- **Функции панели администратора:**
    - Управление списком игнорирования (добавление, удаление и редактирование идентификаторов Telegram).
    - Отслеживайте общее количество полученных запросов.
    - Просматривайте содержание каждого сообщения и сведения об авторе.
    - Добавляйте свои группы для будущих рассылок
    - Генерируйте и скачивайте отчет со списком обращений за определенный период прямиком из админки

## Системные требования
-   PHP 8.2 or higher
-   NPM 10 or higher

## Установка

1.  **Клонируйте репозиторий:**

    ```bash
    git clone https://github.com/Null-ch/telegram-alert-bot.git
    cd telegram-alert-bot
    ```
2.  **Установите зависимости:**

    ```bash
    composer install
    ```

3. **Установите NPM зависимости**

    ```bash
    npm install
    ```

4.  **Соберите проект**

    ```bash
    npm run dev
    ```

## Настройка

1.  **Создайте файл .env :**
   Скопируйте из файла-примера

    ```bash
    cp .env.example .env
    ```

2.  **Создайте файл .env из примера .env.example:**
   Откройте файл `.env` и установите правильные значения для каждого из параметров.
    - `MOONSHINE_TITLE`:  Название вашего проекта, отображаемое в панели администратора.
    - `TELEGRAM_APPEAL_GROUP_ID`: Идентификатор группы Telegram, в которую вы хотите получать уведомления о сообщениях.
    - `TELEGRAM_TEST_BOT_TOKEN`: Токен Telegram-бота, который будет использоваться для сбора сообщений.
    - `TELEGRAM_WEBHOOK_URL`: URL-адрес, по которому ваш Telegram-бот будет получать обновления (должен содержать оба префикса, например, `https://example.com/api/webhook/test`).
    - `TIMEOUT_TO_NEXT_MESSAGE`: Таймер для рассмотрения последующих сообщений от того же пользователя как новых уникальных взаимодействий.
   

3. **Настройка Telegram-бота:**

    - Найдите "config/telegram.php` и настройте данные вашего бота в массиве "боты", следуя приведенному примеру.
    - Создайте новый сервис в "app/Services/Telegram" (следуя структуре существующих сервисов) для работы с вашим конкретным ботом.

4. **Настройка Webhooks:**

    - - Используйте URL для настройки webhook вашего бота (например, `https://example.com/api/webhook/set/test", где "тест" - это префикс вашего бота).
    - Обновите методы `setWebhook`, `removeWebhook` и `handleWebhook` в `app/Http/Controllers/Api/TelegramController.php", чтобы они соответствовали префиксу и сервису вашего бота.

## Использование

- После настройки бот автоматически начнет записывать сообщения в базу данных и направлять уведомления в указанную группу.
- Будет записывать в БД историю по обращениям и пользователей отправивших их, с указанием канала (из какого бота пришло).
- Используйте панель администратора для управления списком игнорируемых сообщений, просмотра статистики и подробной информации о каждом отслеживаемом сообщении.

## Участие

Не стесняйтесь вносить свой вклад в этот проект, отправляя сообщения об ошибках, запросы на добавление новых функций или пул реквесты.

## License

Этот проект лицензирован по лицензии [MIT License](LICENSE).

## Keywords

`telegram`, `bot`, `laravel`, `php`, `database`, `admin`, `moonshine`, `notifications`, `monitoring`, `tracking`, `analytics`

<details>
<summary> <h1>Description in English</h1> </summary>
# Telegram Alert Bot

<p align="center">
    <img src="https://github.com/user-attachments/assets/b7396e85-b7df-4e56-984a-3083b57ef107" width="400">
</p>

<br>

A Telegram bot designed to collect alerts from group and private chats, subsequently storing them in a database for comprehensive usage statistics. This bot provides a powerful tool for tracking and analyzing user interactions in Telegram.

## Features

-   **Real-time Monitoring:**  Monitors Telegram groups and private chats for new messages.
-   **Database Logging:** Records all relevant user interactions into a database for in-depth analysis and reporting.
-   **User Tracking:** Creates records of message authors, allowing for user-specific analysis.
-   **Unique Interaction Tracking:** Tracks unique messages from a user within a configurable time frame to avoid recording repetitive dialogues.
-   **Configurable Timeouts:** Set a timeout (`TIMEOUT_TO_NEXT_MESSAGE`) to define how long to wait before considering a new message from the same user as a unique event. Setting this to `0` will record every single message from users.
-   **Ignore List:** Ignores messages from specific users based on their Telegram ID.
-   **Admin Panel:** Features a fully functional administration panel built with [MoonShine 3.x](https://moonshine.cutcode.dev/)
-   **Admin Panel Features:**
    -   Manage the ignore list (add, remove, and edit Telegram IDs).
    -   Track the total number of received requests.
    -   View the content of each message and author details.

## System Requirements

-   PHP 8.2 or higher
-   NPM 10 or higher

## Installation

1.  **Clone the repository:**

    ```bash
    git clone https://github.com/Null-ch/telegram-alert-bot.git
    cd telegram-alert-bot
    ```
2.  **Install Composer Dependencies:**

    ```bash
    composer install
    ```

3. **Install NPM dependencies**

    ```bash
    npm install
    ```

4.  **Build assets**

    ```bash
    npm run dev
    ```

## Configuration

1.  **Create Environment File:**
   Copy the example env file to your environment and populate it

    ```bash
    cp .env.example .env
    ```

2.  **Configure Environment Variables:**

   Open `.env` file and set the correct values for each of the parameters.
    -   `MOONSHINE_TITLE`:  The name of your project, displayed in the admin panel.
    -   `TELEGRAM_APPEAL_GROUP_ID`:  The Telegram group ID where you want to receive notifications about messages.
    -   `TELEGRAM_TEST_BOT_TOKEN`: The Telegram bot token that will be used to collect messages.
    -   `TELEGRAM_WEBHOOK_URL`: The URL where your Telegram bot will receive updates (must include bot prefix, e.g., `https://example.com/api/webhook/test`).
    -   `TIMEOUT_TO_NEXT_MESSAGE`: The timer to consider subsequent messages from the same user as new, unique interactions.

3.  **Telegram Bot Configuration:**

    -   Locate `config/telegram.php` and configure your bot details in the `bots` array, following the provided example.
    -   Create a new service in `app/Services/Telegram` (following the structure of existing services) to handle your specific bot.

4. **Webhooks Setup:**

    - Use the URL to set up your bot's webhook (e.g.,`https://example.com/api/webhook/set/test`, where `test` is your bot's prefix).
    -   Update the `setWebhook`, `removeWebhook` and `handleWebhook` methods in the `app/Http/Controllers/Api/TelegramController.php` to match your bots prefix and service.

## Usage

-   Once configured, the bot automatically starts logging messages to the database.
-   Use the admin panel to manage the ignore list, view statistics, and see the details of each tracked message.

## Contributing

Feel free to contribute to this project by submitting bug reports, feature requests, or pull requests.

## License

This project is licensed under the [MIT License](LICENSE).

## Keywords

`telegram`, `bot`, `laravel`, `php`, `database`, `admin`, `moonshine`, `notifications`, `monitoring`, `tracking`, `analytics`
</details> 



