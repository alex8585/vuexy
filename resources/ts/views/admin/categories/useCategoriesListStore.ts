import type { AxiosResponse } from "axios";
import { defineStore } from "pinia";
import type { UserProperties } from "@/@fake-db/types";
import type { UserParams } from "@/views/apps/user/types";
import axios from "@axios";

interface Category {
  id?: number;
  name?: string;
}

interface Categories {
  data: Category[];
  meta: {
    rowsNumber: number;
    page: number;
    rowsPerPage: number;
  };
}
interface State {
  _allCategories: Category[];
  categories: Categories;
  category: Category;
  _loading: boolean;
}

export const useCategoriesListStore = function (baseUrl: string) {
  return defineStore("CategoryListStore", {
    id: "categories",
    state: () =>
      <State>{
        _loading: false,
        _allItems: [],
        _item: {},
        _items: [],
        _meta: {
          rowsPerPage: 5,
          page: 1,
          rowsNumber: 0,
        },
      },

    getters: {
      items: (state) => state._items,
      meta: (state) => state._meta,
      loading: (state) => state._loading,
      allTags: (state) => state._allTags,
      rowsNumber: (state) => state._meta.rowsNumber,
    },

    actions: {
      async fetch(params: UserParams) {
        console.log(params);

        this._loading = true;
        let res = await axios.get(baseUrl, { params });

        this._items = res.data.data;
        this._meta = res.data.metaData;
        this._loading = false;
      },
    },
  })();
};
