const axios = require('axios')
const qs = require('qs')

const api = {
    /**
     * @param String url
     * @param Object params
     * @returns {Promise<AxiosResponse<any>>}
     */
    get: (url, params = {}) => axios.get(url, {
        params,
        paramsSerializer: function (params) {
            return qs.stringify(params, { arrayFormat: 'brackets' })
        }
    })
}

export default api
