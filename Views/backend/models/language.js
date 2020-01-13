export default {
    fields: [
        { name: 'id', type: 'integer' },
        { name: 'name', type: 'string', filterable: true },
        { name: 'isoCode', type: 'string', filterable: true },
        { name: 'created', type: 'string' },
        { name: 'changed', type: 'string' }
    ],
    proxy: {
        list: 'backend/language/list',
        detail: 'backend/language/detail',
        save: 'backend/language/save',
        remove: 'backend/language/remove'
    }
}