const lines = [
    '███╗   ███╗██████╗ ██████╗ ███████╗██████╗ ██╗██████╗',
    '████╗ ████║██╔══██╗╚════██╗██╔════╝██╔══██╗██║██╔══██╗',
    '██╔████╔██║██║  ██║ █████╔╝███████╗██████╔╝██║██████╔╝',
    '██║╚██╔╝██║██║  ██║██╔═══╝ ╚════██║██╔═══╝ ██║██╔═══╝',
    '██║ ╚═╝ ██║██████╔╝███████╗███████║██║     ██║██║',
    '╚═╝     ╚═╝╚═════╝ ╚══════╝╚══════╝╚═╝     ╚═╝╚═╝',
];

const segments = 3;
const maxDiag = lines.length - 1 + segments - 1;

function diagColor(index) {
    const t = index / maxDiag;
    const r = Math.round(110 + (5 - 110) * t);
    const g = Math.round(231 + (150 - 231) * t);
    const b = Math.round(183 + (105 - 183) * t);
    const a = (0.25 + 0.75 * t).toFixed(2);

    return `color:rgba(${r},${g},${b},${a});font-weight:bold`;
}

let banner = '\n';
const styles = [];

for (let row = 0; row < lines.length; row++) {
    const line = lines[row];
    const len = line.length;

    for (let seg = 0; seg < segments; seg++) {
        const start = Math.floor(seg * len / segments);
        const end = Math.floor((seg + 1) * len / segments);

        banner += `%c${line.slice(start, end)}`;
        styles.push(diagColor(row + seg));
    }

    banner += '\n';
}

banner += '\n%cConvertisseur Markdown → SPIP\n%cpar Guillaume Orsal\n%chttps://www.orsal.fr';
styles.push('color:#94a3b8;font-size:11px');
styles.push('color:#10b981;font-size:11px;font-weight:bold');
styles.push('color:#10b981;font-size:11px;text-decoration:underline');

console.log(banner, ...styles);
