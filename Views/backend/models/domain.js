export default {
    fields: [
        { name: 'id',      type: 'integer' },
        { name: 'active',  type: 'boolean' },
        { name: 'label',   type: 'string', filterable: true },
        { name: 'host',    type: 'string', filterable: true },
        { name: 'hosts',   type: 'string', filterable: true },
        { name: 'secure',  type: 'boolean' },
        { name: 'created', type: 'string' },
        { name: 'changed', type: 'string' }
    ],
    proxy: {
        list: 'backend/domain/list',
        detail: 'backend/domain/detail',
        save: 'backend/domain/save',
        remove: 'backend/domain/remove'
    }
}