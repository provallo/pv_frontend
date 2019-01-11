export default {
    fields: [
        { name: 'id', type: 'integer' },
        { name: 'name', type: 'string', filterable: true },
        { name: 'created', type: 'string' },
    ],
    proxy: {
        list: 'backend/theme/list',
        detail: 'backend/theme/detail',
        save: 'backend/theme/save',
        remove: 'backend/theme/remove'
    }
}