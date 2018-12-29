export default function () {
    const { createModel } = require('@/models')
    
    ProVallo.$router.addRoutes([
        {
            name: 'page.index',
            path: '/pages',
            component: require('./views/page/Index.vue').default
        },
        {
            name: 'domain.index',
            path: '/domains',
            component: require('./views/domain/Index.vue').default
        }
    ])
    
    ProVallo.$models.page = createModel(require('./models/page').default)
    ProVallo.$models.domain = createModel(require('./models/domain').default)
    
    require('./assets/less/all.less')
}