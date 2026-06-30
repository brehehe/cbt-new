import http from 'k6/http';

export const options = {
    vus: 500, // virtual users
    duration: '1m',
};

export default function () {
    http.get('https://procbt.id/login');
}