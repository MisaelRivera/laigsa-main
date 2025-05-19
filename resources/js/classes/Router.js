import { router } from "@inertiajs/vue3";
import { push } from "notivue";
export default class Router {
    constructor(data) {
        this.url = data.url;
        this.action = data.action ?? 'get';
        this.message = data.message ?? null;
        this.data = data.data ?? {};
    }

    sendMessage() {
        return () => {
            if (this.data.message) {
                push.success(this.data.message);
            }
        }
    }

    dispatch () {
        switch (this.action) {
            case 'get':
                router.get(url);
            break;
            case 'post':
                router.post(this.url, this.data, {
                    onSuccess: this.sendMessage()
                });
            break;
            case 'put':
                router.put(this.url, this.data, {
                    onSuccess: this.sendMessage()
                });
            break;
            case 'delete':
                router.delete(this.url, {
                    onSuccess: this.sendMessage()
                });
            break;
        }
    }
}