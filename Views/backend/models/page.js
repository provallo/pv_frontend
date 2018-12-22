export default {
    fields: [
        { name: 'id', type: 'integer' },
        { name: 'parentID', type: 'integer' },
        { name: 'active', type: 'boolean' },
        { name: 'route', type: 'string', filterable: true },
        { name: 'label', type: 'string', filterable: true },
        { name: 'type', type: 'integer' },
        { name: 'data', type: 'string' },
        { name: 'position', type: 'integer' },
        { name: 'created', type: 'string' },
        { name: 'changed', type: 'string' },
    ],
    proxy: {
        list: 'backend/page/list',
        detail: 'backend/page/detail',
        save: 'backend/page/save',
        remove: 'backend/page/remove'
    }
}