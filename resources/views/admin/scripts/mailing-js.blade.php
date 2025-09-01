<style>
/* Базовые стили для кастомного select */
.custom-select {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    width: 100%;
    padding: 0.375rem 0.75rem;
    font-size: 1rem;
    font-weight: 400;
    line-height: 1.5;
    color: #495057; /* Цвет текста */
    background-color: #fff; /* Фон для светлой темы */
    background-image: none;
    border: 1px solid #ced4da;
    border-radius: 0.25rem;
    transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    position: relative;
    height: 300px;
}

.custom-select:focus {
    border-color: #80bdff;
    outline: 0;
    box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
}

.custom-select:disabled {
    background-color: #e9ecef;
    height: 50px;
    opacity: 1;
    cursor: not-allowed;
}

/* Стили для option */
.custom-select option {
    padding: 10px 20px;
    background-color: #f8f9fa;
    color: #495057;
    border-bottom: 1px solid #ced4da;
    border-top: 1px solid #ced4da;
}

/* Стили для выбранных тегов */
.selected-tags {
    display: flex;
    flex-wrap: wrap;
    margin-bottom: 1rem;
}

.selected-tag {
    background-color: #007bff;
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    margin: 2px;
    display: inline-flex;
    align-items: center;
}

.selected-tag .remove-tag {
    margin-left: 5px;
    cursor: pointer;
}

.custom-select option:checked {
    background-color: #007bff;
    color: white;
}

/* .custom-select option:last-child {
    border-bottom: none;
} */

/* Стили для темной темы */
html.dark .custom-select {
    background-color: #1b263c; /* Темный фон */
    color: #ffffff; /* Белый текст */
    border: 1px solid #495057; /* Темная граница */
    height: 300px;
}

html.dark .custom-select:focus {
    border-color: #80bdff;
    box-shadow: 0 0 0 0.2rem rgba(38, 143, 255, 0.25);
}

html.dark .custom-select option {
    background-color: rgb(27, 38, 60, 0.7); /* Темный фон для option */
    color: #ffffff; /* Белый текст для option */
}

html.dark .custom-select option:checked {
    background-color: #007bff;
    color: white;
}

html.dark .selected-tag {
    background-color: #007bff;
    color: white;
}

html.dark .custom-select:disabled {
    background-color: #1b263c;
    height: 50px;
    opacity: 1;
    cursor: not-allowed;
}


</style>

<script>
   document.addEventListener('DOMContentLoaded', () => {
    const accountSelect = document.getElementById('account-select');
    const chatSelect = document.getElementById('adminGroupChats');

    // Функция для применения темной темы, если установлен класс 'dark'
    const applyDarkTheme = () => {
        const isDark = document.documentElement.classList.contains('dark');
        
        if (isDark) {
            // Применить стиль темной темы
            document.body.classList.add('dark');
        } else {
            // Убрать стиль темной темы, если класс 'dark' отсутствует
            document.body.classList.remove('dark');
        }
    };

    // Проверка на старте страницы
    applyDarkTheme();

    // Следить за изменениями состояния класса 'dark' на элементе <html>
    const observer = new MutationObserver(applyDarkTheme);
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

    accountSelect?.addEventListener('change', async () => {
        const account = accountSelect.value;

        if (!account) {
            chatSelect.disabled = true;
            chatSelect.innerHTML = ''; // Очистить список чатов
            return;
        }

        // Запрос на сервер для получения чатов по аккаунту
        const response = await fetch(`/api/group-chats?account=${account}`);

        const data = await response.json();

        chatSelect.innerHTML = ''; // Очистить текущий список чатов

        // Если чаты получены, заполняем список
        if (Object.keys(data).length > 0) {
            for (const [value, label] of Object.entries(data)) {
                const option = document.createElement('option');
                option.value = value;
                option.textContent = label;
                chatSelect.appendChild(option);
            }

            chatSelect.disabled = false; // Сделать Select доступным
        } else {
            chatSelect.disabled = true; // Оставить Select заблокированным, если нет чатов
        }
    });
});
</script>
