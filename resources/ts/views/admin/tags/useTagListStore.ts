import type { AxiosResponse } from 'axios'
import { defineStore } from 'pinia'
import type { UserProperties } from '@/@fake-db/types'
import type { UserParams } from '@/views/apps/user/types'
import axios from '@axios'

let baseUrl = '/api/v1/tags' 
export const useTagListStore = defineStore('TagListStore', {
  actions: {

    fetch(params: UserParams) { 
      console.log(params);
      let users = axios.get(baseUrl, { params }) 
      return users;
    },


    addItem(data: UserProperties) {
      return new Promise((resolve, reject) => {
        axios.post(baseUrl, {
            ...data,
        }).then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    updateItem(data: UserProperties) {
      return new Promise((resolve, reject) => {
        axios.put(`${baseUrl}/${data.id}`, {
            name:data.name,
        }).then(response => resolve(response))
          .catch(error => reject(error))
      })
    },

    deleteItem(id: number) {
      return new Promise((resolve, reject) => {
        axios.delete(`${baseUrl}/${id}`).then(response => resolve(response)).catch(error => reject(error))
    })

    },

    fetchItem(id: number) {
      return new Promise<AxiosResponse>((resolve, reject) => {
        axios.get(`/apps/users/${id}`).then(response => resolve(response)).catch(error => reject(error))
      })
    }

  },
})
