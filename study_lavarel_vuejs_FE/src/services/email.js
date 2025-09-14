import axios from 'axios'

const API_BASE = 'http://localhost:8000/api/v1/emails'

export const getEmails = () => axios.get(API_BASE)
export const getEmail = (id) => axios.get(`${API_BASE}/${id}`)
export const createEmail = (data) => axios.post(API_BASE, data)
export const updateEmail = (id, data) => axios.put(`${API_BASE}/${id}`, data)
export const deleteEmail = (id) => axios.delete(`${API_BASE}/${id}`)
