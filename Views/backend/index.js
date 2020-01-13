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
            path: '/config/domains',
            component: require('./views/domain/Index.vue').default
        },
        {
            name: 'theme.index',
            path: '/themes',
            component: require('./views/theme/Index.vue').default
        },
        {
            name: 'language.index',
            path: '/config/languages',
            component: require('./views/language/Index.vue').default
        }
    ])

    ProVallo.$models.page = createModel(require('./models/page').default)
    ProVallo.$models.domain = createModel(require('./models/domain').default)
    ProVallo.$models.theme = createModel(require('./models/theme').default)
    ProVallo.$models.language = createModel(require('./models/language').default)

    require('./assets/less/all.less')
}