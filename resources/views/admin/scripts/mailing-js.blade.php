@include('admin.scripts._reactive-select')

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const accountSelect = document.getElementById('account-select');
        const chatGroupSelect = document.getElementById('chatGroupSelect');

        accountSelect?.addEventListener('change', async () => {
            const account = accountSelect.value;

            if (!account) {
                reactiveSelect.setOptions('adminGroupChats', []);
                reactiveSelect.setOptions('chatGroupSelect', []);
                return;
            }

            const chatsResponse = await fetch(`/api/group-chats?account=${account}`);
            const chats = await chatsResponse.json();
            reactiveSelect.setOptions(
                'adminGroupChats',
                Object.entries(chats).map(([value, label]) => ({ value, label })),
            );

            const groupsResponse = await fetch(`/api/chat-groups?account=${account}`);
            const groups = await groupsResponse.json();
            reactiveSelect.setOptions(
                'chatGroupSelect',
                Object.entries(groups).map(([value, label]) => ({ value, label })),
            );
        });

        // При выборе сохранённой группы чатов — отметить её чаты в списке выбора чатов
        chatGroupSelect?.addEventListener('change', async () => {
            const chatGroupId = chatGroupSelect.value;

            if (!chatGroupId) {
                return;
            }

            const response = await fetch(`/api/chat-groups/${chatGroupId}/chats`);
            const chatIds = await response.json();

            reactiveSelect.selectValues('adminGroupChats', chatIds);
        });
    });
</script>
