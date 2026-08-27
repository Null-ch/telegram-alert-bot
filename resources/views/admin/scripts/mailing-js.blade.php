@include('admin.scripts._select-styles')

<script>
   document.addEventListener('DOMContentLoaded', () => {
    const accountSelect = document.getElementById('account-select');
    const chatGroupSelect = document.getElementById('chatGroupSelect');
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

    const resetChatGroupSelect = () => {
        if (!chatGroupSelect) return;
        chatGroupSelect.disabled = true;
        chatGroupSelect.innerHTML = '';
    };

    const resetChatSelect = () => {
        chatSelect.disabled = true;
        chatSelect.innerHTML = '';
    };

    accountSelect?.addEventListener('change', async () => {
        const account = accountSelect.value;

        if (!account) {
            resetChatGroupSelect();
            resetChatSelect();
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

        // Запрос на сервер для получения сохранённых групп чатов по аккаунту
        if (chatGroupSelect) {
            const groupsResponse = await fetch(`/api/chat-groups?account=${account}`);
            const groups = await groupsResponse.json();

            chatGroupSelect.innerHTML = '';

            const placeholder = document.createElement('option');
            placeholder.value = '';
            placeholder.textContent = 'Без группы (выбрать чаты вручную)';
            chatGroupSelect.appendChild(placeholder);

            if (Object.keys(groups).length > 0) {
                for (const [value, label] of Object.entries(groups)) {
                    const option = document.createElement('option');
                    option.value = value;
                    option.textContent = label;
                    chatGroupSelect.appendChild(option);
                }

                chatGroupSelect.disabled = false;
            } else {
                chatGroupSelect.disabled = true;
            }
        }
    });

    // При выборе сохранённой группы чатов — отметить её чаты в списке выбора чатов
    chatGroupSelect?.addEventListener('change', async () => {
        const chatGroupId = chatGroupSelect.value;

        if (!chatGroupId) {
            return;
        }

        const response = await fetch(`/api/chat-groups/${chatGroupId}/chats`);
        const chatIds = await response.json();

        Array.from(chatSelect.options).forEach((option) => {
            option.selected = chatIds.includes(Number(option.value));
        });
    });
});
</script>
