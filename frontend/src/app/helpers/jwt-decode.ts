export class JwtDecode {

    public static decode(jwtRaw: string): JwtObject {
        let token: JwtObject = {
            raw: jwtRaw,
            header: JSON.parse(window.atob(jwtRaw.split('.')[0])),
            payload: JSON.parse(window.atob(jwtRaw.split('.')[1])),
        };

        return token;
    }
}

interface JwtObject {
    raw: string;
    header: any;
    payload: { exp: number };
}