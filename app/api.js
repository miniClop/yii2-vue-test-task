import axios from 'axios';

// TODO: baseURL вынести в env
const axiosClient = axios.create({
    baseURL: 'http://test-task.local',
    responseType: 'json',
    headers: {
        'Content-Type': 'application/json',
        Accept: 'application/json',
    },
});

export default {
    helpGet: url => axiosClient.get(url).then(res => res.data),
    helpPost: (url, data) => axiosClient.post(url, data).then(res => res.data),
    helpPatch: (url, data) => axiosClient.patch(url, data).then(res => res.data),
    helpDelete: (url) => axiosClient.delete(url)
}
