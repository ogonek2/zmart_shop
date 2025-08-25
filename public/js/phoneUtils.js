// phoneUtils.js

export function isValidUAPhone(phone) {
    const cleaned = phone.replace(/\D/g, '');
    if (cleaned.length !== 12 || !cleaned.startsWith('380')) return false;

    const operatorCode = '0' + cleaned.slice(3, 5);

    const validCodes = [
        '039', '050', '063', '066', '067', '068',
        '091', '092', '093', '094', '095', '096', '097', '098', '099'
    ];

    return validCodes.includes(operatorCode);
}