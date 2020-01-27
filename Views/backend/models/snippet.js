export default {
    fields: [
        { name: 'id', type: 'integer' },
        { name: 'name', type: 'string', filterable: true },
        { name: 'values', type: 'array' }
    ],
    proxy: {
        list: 'backend/snippet/list',
        detail: 'backend/snippet/detail',
        save: 'backend/snippet/save',
        remove: 'backend/snippet/remove'
    }
}