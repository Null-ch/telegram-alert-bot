<script>
    // Утилита для программного управления select-полями MoonShine (Choices.js),
    // когда список опций одного поля зависит от значения другого (например, чаты зависят от аккаунта).
    window.reactiveSelect = window.reactiveSelect || {
        instance(selectId) {
            const el = document.getElementById(selectId);
            if (!el) return null;

            return window.Alpine?.$data(el)?.choicesInstance ?? null;
        },

        // Полностью заменяет список доступных опций. options: [{value, label}]
        setOptions(selectId, options, selectedValues = []) {
            const instance = this.instance(selectId);
            if (!instance) return;

            const selected = new Set((selectedValues || []).map(String));
            const choices = (options || []).map(({ value, label }) => ({
                value: String(value),
                label,
                selected: selected.has(String(value)),
            }));

            instance.clearStore();
            instance.setChoices(choices, 'value', 'label', true);

            if (choices.length > 0) {
                instance.enable();
            } else {
                instance.disable();
            }
        },

        // Меняет только текущий выбор, не трогая список доступных опций
        selectValues(selectId, values) {
            const instance = this.instance(selectId);
            if (!instance) return;

            instance.removeActiveItems();
            instance.setChoiceByValue((values || []).map(String));
        },

        disable(selectId) {
            this.instance(selectId)?.disable();
        },
    };
</script>
