@include('admin.scripts._reactive-select')

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const accountSelect = document.getElementById('chat-group-account-select');

        accountSelect?.addEventListener('change', async () => {
            const account = accountSelect.value;

            if (!account) {
                reactiveSelect.setOptions('chatGroupChatsSelect', []);
                return;
            }

            const response = await fetch(`/api/group-chats?account=${account}`);
            const data = await response.json();

            reactiveSelect.setOptions(
                'chatGroupChatsSelect',
                Object.entries(data).map(([value, label]) => ({ value, label })),
            );
        });
    });
</script>
