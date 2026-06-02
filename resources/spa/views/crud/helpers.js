import { useReferencesStore } from '@/stores/references';

/** Read a possibly-dotted path ("user.email") off an object. */
export function getByPath(obj, path) {
    if (!path) return undefined;
    return path.split('.').reduce((acc, k) => (acc == null ? acc : acc[k]), obj);
}

/**
 * Normalise a list response into { items, page }. Handles both shapes the API
 * emits: JsonResource collections ({ data, meta:{...}, links }) and raw Eloquent
 * paginators ({ data, current_page, last_page, per_page, total, ... }).
 */
export function normalizePage(body, fallbackPerPage = 25) {
    const meta = body?.meta ?? body ?? {};
    return {
        items: body?.data ?? [],
        page: {
            current_page: meta.current_page ?? 1,
            last_page: meta.last_page ?? 1,
            per_page: meta.per_page ?? fallbackPerPage,
            total: meta.total ?? (body?.data?.length ?? 0),
        },
    };
}

/**
 * Resolve a field/filter `options` spec into [{ label, value }]:
 *  - "references:<bundleKey>" → from the references store
 *  - "Label:value,Label2:value2" → static csv
 *  - "none" / falsy → null (caller falls back to a plain input)
 */
export function resolveOptions(spec) {
    if (!spec || spec === 'none') return null;
    if (spec.startsWith('references:')) {
        return useReferencesStore().options(spec.slice('references:'.length));
    }
    return spec
        .split(',')
        .map((pair) => {
            const idx = pair.lastIndexOf(':');
            const label = idx === -1 ? pair : pair.slice(0, idx);
            const value = idx === -1 ? pair : pair.slice(idx + 1);
            return { label: label.trim(), value: value.trim() === '' ? null : value.trim() };
        });
}

export function toDate(v) {
    return v ? new Date(`${String(v).slice(0, 10)}T00:00:00`) : null;
}

export function toYmd(v) {
    if (!v) return null;
    if (typeof v === 'string') return v;
    const d = v;
    return `${d.getFullYear()}-${String(d.getMonth() + 1).padStart(2, '0')}-${String(d.getDate()).padStart(2, '0')}`;
}

export function fmtDate(v) {
    return v ? new Date(v).toLocaleDateString() : '—';
}

export function fmtDateTime(v) {
    return v ? new Date(v).toLocaleString() : '—';
}

export function fmtMoney(v) {
    if (v == null || v === '') return '—';
    return `$${Number(v).toFixed(2)}`;
}
