import Token from './Token';
import AppStorage from './AppStorage';

class User {

    responseAfterLogin(res){
        const access_token = res.data.access_token;
        const username     = res.data.name;
        if (Token.isValid(access_token)){
            AppStorage.store(access_token, username);
        }
    }

    hasToken(){
        const storedToken = localStorage.getItem('token');
        if (storedToken){
            return Token.isValid(storedToken);
        }
        return false;
    }

    loggedIn(){
        return this.hasToken();
    }

    logout() {
        AppStorage.clear();
    }

    name(){
        if (this.loggedIn()){
            return AppStorage.getUser();
        }
    }

    id(){
        if (this.loggedIn()){
            const payload = Token.payload(AppStorage.getToken());
            return payload.sub;
        }
        return false;
    }

    own(id) {
        return this.id() == id
    }

    admin() {
        return this.id() == 1
    }
}
export default User = new User();
