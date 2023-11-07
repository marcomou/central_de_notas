export class GeocodeConverter {

    public static parseDMS(input) {
        var parts = input.split(/[^\d\w\.]+/);
        var lat = this.convertDMSToDD(parts[0], parts[1], parts[2], parts[3]);
        var lon = this.convertDMSToDD(parts[4], parts[5], parts[6], parts[7]);

        return {lat, lon};
    }

    public static convertDMSToDD(degrees, minutes, seconds, direction) {

        var dd = Number(degrees) + Number(minutes)/60 + Number(seconds)/(60*60);

        if (direction === "S" || direction === "W") {
            dd = dd * -1;
        }

        return dd;
    }
}
