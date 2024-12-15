<?php

return [

    /*
        |--------------------------------------------------------------------------
        | Правила валидации
        |--------------------------------------------------------------------------
        |
        | Следующие строки содержат стандартные сообщения об ошибках, используемые
        | классом валидатора. Некоторые из этих правил имеют несколько версий,
        | такие как правила размера. Не стесняйтесь настроить каждое из этих сообщений здесь.
        |
        */

    'accepted' => 'Поле :attribute должно быть принято.',
    'accepted_if' => 'Поле :attribute должно быть принято при :other = :value.',
    'active_url' => 'Поле :attribute не является корректным URL.',
    'after' => 'Поле :attribute должно быть датой после :date.',
    'after_or_equal' => 'Поле :attribute должно быть датой после или равной :date.',
    'alpha' => 'Поле :attribute должно содержать только буквы.',
    'alpha_dash' => 'Поле :attribute должно содержать только буквы, цифры, дефисы и подчеркивания.',
    'alpha_num' => 'Поле :attribute должно содержать только буквы и цифры.',
    'array' => 'Поле :attribute должно быть массивом.',
    'before' => 'Поле :attribute должно быть датой до :date.',
    'before_or_equal' => 'Поле :attribute должно быть датой до или равной :date.',
    'between' => [
        'array' => 'Поле :attribute должно иметь от :min до :max элементов.',
        'file' => 'Поле :attribute должно быть от :min до :max килобайт.',
        'numeric' => 'Поле :attribute должно быть от :min до :max.',
        'string' => 'Поле :attribute должно содержать от :min до :max символов.',
    ],
    'boolean' => 'Поле :attribute должно быть true или false.',
    'confirmed' => 'Подтверждение поля :attribute не соответствует.',
    'current_password' => 'Пароль неверен.',
    'date' => 'Поле :attribute не является корректной датой.',
    'date_equals' => 'Поле :attribute должно быть датой равной :date.',
    'date_format' => 'Поле :attribute не соответствует формату :format.',
    'declined' => 'Поле :attribute должно быть отклонено.',
    'declined_if' => 'Поле :attribute должно быть отклонено при :other = :value.',
    'different' => 'Поле :attribute и :other должны отличаться.',
    'digits' => 'Поле :attribute должно быть :digits цифрами.',
    'digits_between' => 'Поле :attribute должно содержать от :min до :max цифр.',
    'dimensions' => 'Поле :attribute имеет недопустимые размеры изображения.',
    'distinct' => 'Поле :attribute содержит дубликатное значение.',
    'email' => 'Поле :attribute должно быть корректным адресом электронной почты.',
    'ends_with' => 'Поле :attribute должно заканчиваться одним из следующих: :values.',
    'enum' => 'Выбранное значение поля :attribute недопустимо.',
    'exists' => 'Выбранное значение поля :attribute недопустимо.',
    'file' => 'Поле :attribute должно быть файлом.',
    'filled' => 'Поле :attribute должно иметь значение.',
    'gt' => [
        'array' => 'Поле :attribute должно содержать больше :value элементов.',
        'file' => 'Поле :attribute должно превышать :value килобайт.',
        'numeric' => 'Поле :attribute должно превышать :value.',
        'string' => 'Поле :attribute должно превышать :value символов.',
    ],
    'gte' => [
        'array' => 'Поле :attribute должно содержать не менее :value элементов.',
        'file' => 'Поле :attribute должно быть не менее :value килобайт.',
        'numeric' => 'Поле :attribute должно быть не менее :value.',
        'string' => 'Поле :attribute должно быть не менее :value символов.',
    ],
    'image' => 'Поле :attribute должно быть изображением.',
    'in' => 'Выбранное значение поля :attribute недопустимо.',
    'in_array' => 'Поле :attribute не существует в :other.',
    'integer' => 'Поле :attribute должно быть целым числом.',
    'ip' => 'Поле :attribute должно быть корректным адресом IP.',
    'ipv4' => 'Поле :attribute должно быть корректным адресом IPv4.',
    'ipv6' => 'Поле :attribute должно быть корректным адресом IPv6.',
    'json' => 'Поле :attribute должно быть корректной строкой JSON.',
    'lt' => [
        'array' => 'Поле :attribute должно содержать менее :value элементов.',
        'file' => 'Поле :attribute должно быть меньше :value килобайт.',
        'numeric' => 'Поле :attribute должно быть меньше :value.',
        'string' => 'Поле :attribute должно быть меньше :value символов.',
    ],
    'lte' => [
        'array' => 'Поле :attribute не должно содержать более :value элементов.',
        'file' => 'Поле :attribute должно быть не более :value килобайт.',
        'numeric' => 'Поле :attribute должно быть не более :value.',
        'string' => 'Поле :attribute должно быть не более :value символов.',
    ],
    'mac_address' => 'Поле :attribute должно быть корректным адресом MAC.',
    'max' => [
        'array' => 'Поле :attribute не должно содержать более :max элементов.',
        'file' => 'Поле :attribute не должно превышать :max килобайт.',
        'numeric' => 'Поле :attribute не должно превышать :max.',
        'string' => 'Поле :attribute не должно превышать :max символов.',
    ],
    'mimes' => 'Поле :attribute должно быть файлом типа: :values.',
    'mimetypes' => 'Поле :attribute должно быть файлом типа: :values.',
    'min' => [
        'array' => 'Поле :attribute должно содержать не менее :min элементов.',
        'file' => 'Поле :attribute должно быть не менее :min килобайт.',
        'numeric' => 'Поле :attribute должно быть не менее :min.',
        'string' => 'Поле :attribute должно быть не менее :min символов.',
    ],
    'multiple_of' => 'Поле :attribute должно быть кратным :value.',
    'not_in' => 'Выбранное значение поля :attribute недопустимо.',
    'not_regex' => 'Формат поля :attribute недопустим.',
    'numeric' => 'Поле :attribute должно быть числом.',
    'present' => 'Поле :attribute должно присутствовать.',
    'prohibited' => 'Поле :attribute запрещено.',
    'prohibited_if' => 'Поле :attribute запрещено при :other = :value.',
    'prohibited_unless' => 'Поле :attribute запрещено, если :other не в :values.',
    'prohibits' => 'Поле :attribute запрещает наличие :other.',
    'regex' => 'Формат поля :attribute недопустим.',
    'required' => 'Поле :attribute обязательно для заполнения.',
    'required_array_keys' => 'Поле :attribute должно содержать элементы для: :values.',
    'required_if' => 'Поле :attribute обязательно при :other = :value.',
    'required_unless' => 'Поле :attribute обязательно, если не в :values.',
    'required_with' => 'Поле :attribute обязательно при наличии :values.',
    'required_with_all' => 'Поле :attribute обязательно при наличии любых :values.',
    'required_without' => 'Поле :attribute обязательно при отсутствии :values.',
    'required_without_all' => 'Поле :attribute обязательно при отсутствии всех :values.',
    'same' => 'Поле :attribute и :other должны совпадать.',
    'size' => [
        'array' => 'Поле :attribute должно содержать :size элементов.',
        'file' => 'Поле :attribute должно быть :size килобайт.',
        'numeric' => 'Поле :attribute должно быть :size.',
        'string' => 'Поле :attribute должно быть :size символов.',
    ],
    'starts_with' => 'Поле :attribute должно начинаться с одного из следующих: :values.',
    'string' => 'Поле :attribute должно быть строкой.',
    'timezone' => 'Поле :attribute должно быть корректным часовым поясом.',
    'unique' => 'Поле :attribute уже занято.',
    'uploaded' => 'Загрузка поля :attribute не удалась.',
    'url' => 'Поле :attribute должно быть корректным URL адресом.',
    'uuid' => 'Поле :attribute должно быть корректным UUID.',

    /*
|--------------------------------------------------------------------------
| Custom Validation Language Lines
|--------------------------------------------------------------------------
|
| Здесь вы можете указать пользовательские сообщения о валидации для
| атрибутов, используя конвенцию "attribute.rule" для названия строк.
| Это позволяет быстро указать конкретную пользовательскую строку
| для определенного правила атрибута.
|
*/

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
|--------------------------------------------------------------------------
| Custom Validation Attributes
|--------------------------------------------------------------------------
|
| Следующие строки используются для замены плейсхолдера атрибута
| на более читаемое выражение, например "E-Mail Address" вместо
| "email". Это просто помогает сделать нашу сообщение более экспрессивным.
|
*/

    'attributes' => [],

];
