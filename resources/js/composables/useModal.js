import { ref } from "vue";
import Router from "@/classes/Router";
export  function useModal (data) {
    const isOpen = ref(data.isOpen);
    let item = null;
    const Router = new Router(data);
    const openModal = (itemData = null) => {
        isOpen.value = true;
        item = itemData;
    };

    const closeModal = () => {
        isOpen.value = false;
        if (item) item = null; 
    };

    const handleAction = () => {
        
    };

}