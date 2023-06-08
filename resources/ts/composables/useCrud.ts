import { ref } from "vue";
import axios from '@axios'
export default function useCrud(baseUrl: string ) {

    async function addItem(data: UserProperties) {
        let res = await  axios.post(baseUrl, data)
        return res
    }

    async function updateItem(data: UserProperties) {
        let sendData = { ...data }
        delete sendData.id
        let res = await axios.put(`${baseUrl}/${data.id}`, sendData)
        return res
    }

    async function deleteItem(id: number) {
        let res = await axios.delete(`${baseUrl}/${id}`)
        return res
    }

    async function fetchItem(id: number) {
        let res = await axios.get(`${baseUrl}/${id}`)
        return res
    }

  return { addItem, deleteItem, updateItem, fetchItem };
}
