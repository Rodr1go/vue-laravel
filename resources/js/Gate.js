export default class Gate {
    constructor(user) {
        this.user = user;
    }

    isAdmin() {
        return this.user.type === 'admin';
    }

    isUser() {
        return this.user.type === 'user';
    }

    isAdminOrAuthor() {
        if(this.user.type === 'admin' || this.user.type === 'autor') {
            return true;
        }
    }

    isAuthorOrUser() {
        if(this.user.type === 'autor' || this.user.type === 'user') {
            return true;
        }
    }
}