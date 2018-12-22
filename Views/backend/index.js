module.exports = function () {
    const { createModel } = require('@/models')
    
    ProVallo.$router.addRoutes([
        {
            name: 'page.index',
            path: '/pages',
            component: require('./views/page/Index.vue').default
        }
    ])
    
    ProVallo.$models.page = createModel(require('./models/page').default)
}