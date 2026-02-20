import http from 'k6/http';
import { sleep, check } from 'k6';

export const options = {
  vus: 50, // 50 виртуальных пользователей
  duration: '30s', // Тестим 30 секунд
};

export default function () {
  const res = http.get('http://app:8000/'); // Стучимся в наш контейнер app
  check(res, {
    'status is 200': (r) => r.status === 200,
    'load time < 200ms': (r) => r.timings.duration < 200,
  });
  sleep(1);
}