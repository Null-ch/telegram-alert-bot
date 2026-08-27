@include('admin.scripts._select-styles')

<script>
   document.addEventListener('DOMContentLoaded', () => {
    const accountSelect = document.getElementById('chat-group-account-select');
    const chatsSelect = document.getElementById('chatGroupChatsSelect');
    const initiallySelectedChatIds = @json($selectedChatIds ?? []);

    const applyDarkTheme = () => {
        const isDark = document.documentElement.classList.contains('dark');

        if (isDark) {
            document.body.classList.add('dark');
        } else {
            document.body.classList.remove('dark');
        }
    };

    applyDarkTheme();

    const observer = new MutationObserver(applyDarkTheme);
    observer.observe(document.documentElement, { attributes: true, attributeFilter: ['class'] });

    // Отметить уже привязанные к группе чаты (актуально при редактировании)
    if (chatsSelect && initiallySelectedChatIds.length > 0) {
        Array.from(chatsSelect.options).forEach((option) => {
            option.selected = initiallySelectedChatIds.includes(Number(option.value));
        });
    }

    accountSelect?.addEventListener('change', async () => {
        const account = accountSelect.value;

        if (!account) {
            chatsSelect.disabled = true;
            chatsSelect.innerHTML = '';
            return;
        }

        const response = await fetch(`/api/group-chats?account=${account}`);
        const data = await response.json();

        chatsSelect.innerHTML = '';

        if (Object.keys(data).length > 0) {
            for (const [value, label] of Object.entries(data)) {
                const option = document.createElement('option');
                option.value = value;
                option.textContent = label;
                chatsSelect.appendChild(option);
            }

            chatsSelect.disabled = false;
        } else {
            chatsSelect.disabled = true;
        }
    });
});
</script>
