import { api } from '../api';
export async function getTags() {
  const res = await api.get('/tags');
  return res.data;
}

