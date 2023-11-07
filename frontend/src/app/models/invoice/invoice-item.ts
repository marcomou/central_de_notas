export class InvoiceItem {

    static inferedIsPackageStatuses = {
        'true': 'Sim',
        'false': 'Não',
    };

    public product_description: any;
    public sequence_invoice_number!: number;
    public mass_kg!: number;
    public ncm_material!: string;
    public infered_is_package!: boolean;
    public ncm!: string

    getInferedIsPackage() {
        let infered_is_package = String(this.infered_is_package).toString() || 'true';

        return InvoiceItem.inferedIsPackageStatuses[infered_is_package as 'true' | 'false'];
    }

    getMassT() {
        return (this.mass_kg || 0) * 0.001;
    }

    protected static setonce_properties = [
    ];

    protected static modifiable_properties = [
    ];

}
