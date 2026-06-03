const STORAGE_PREFIX = 'batu-kuda-scroll';
const POSITION_PREFIX = `${STORAGE_PREFIX}:position:`;
const PENDING_KEY = `${STORAGE_PREFIX}:pending`;
const PENDING_MAX_AGE = 5 * 60 * 1000;

const getPath = () => window.location.pathname;
const getPositionKey = () => `${POSITION_PREFIX}${getPath()}`;

const safeStorage = (callback, fallback = null) => {
    try {
        return callback(window.sessionStorage);
    } catch (_) {
        return fallback;
    }
};

const savePosition = (markPending = false) => {
    safeStorage((storage) => {
        storage.setItem(getPositionKey(), JSON.stringify({
            x: window.scrollX,
            y: window.scrollY,
        }));

        if (markPending) {
            storage.setItem(PENDING_KEY, JSON.stringify({
                path: getPath(),
                at: Date.now(),
            }));
        }
    });
};

const readPending = () => safeStorage((storage) => {
    const rawPending = storage.getItem(PENDING_KEY);

    if (!rawPending) {
        return null;
    }

    return JSON.parse(rawPending);
});

const readPosition = () => safeStorage((storage) => {
    const rawPosition = storage.getItem(getPositionKey());

    if (!rawPosition) {
        return null;
    }

    return JSON.parse(rawPosition);
});

const clearPending = () => {
    safeStorage((storage) => storage.removeItem(PENDING_KEY));
};

const shouldRestore = () => {
    if (window.location.hash) {
        clearPending();
        return false;
    }

    const pending = readPending();

    if (!pending || pending.path !== getPath()) {
        clearPending();
        return false;
    }

    if (Date.now() - Number(pending.at || 0) > PENDING_MAX_AGE) {
        clearPending();
        return false;
    }

    return true;
};

const restorePosition = (position) => {
    const maxY = Math.max(0, document.documentElement.scrollHeight - window.innerHeight);
    const maxX = Math.max(0, document.documentElement.scrollWidth - window.innerWidth);

    window.scrollTo({
        left: Math.min(Math.max(Number(position.x || 0), 0), maxX),
        top: Math.min(Math.max(Number(position.y || 0), 0), maxY),
        behavior: 'auto',
    });
};

const scheduleRestore = () => {
    if (!shouldRestore()) {
        return;
    }

    const position = readPosition();
    clearPending();

    if (!position || (!position.x && !position.y)) {
        return;
    }

    requestAnimationFrame(() => restorePosition(position));

    window.addEventListener('load', () => {
        restorePosition(position);
        window.setTimeout(() => restorePosition(position), 150);
        window.setTimeout(() => restorePosition(position), 450);
    }, { once: true });
};

const bindScrollRestore = () => {
    let scrollTimer;

    window.addEventListener('scroll', () => {
        window.clearTimeout(scrollTimer);
        scrollTimer = window.setTimeout(() => savePosition(), 150);
    }, { passive: true });

    window.addEventListener('pagehide', () => savePosition(true));
    window.addEventListener('beforeunload', () => savePosition(true));

    scheduleRestore();
};

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', bindScrollRestore, { once: true });
} else {
    bindScrollRestore();
}
