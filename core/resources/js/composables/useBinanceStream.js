import { onBeforeUnmount, onMounted } from 'vue';

/**
 * Subscribe to a Binance public websocket stream (same source the Blade
 * trade page used) with automatic reconnect while the component lives.
 */
export function useBinanceStream(stream, onMessage) {
    let ws;
    let closed = false;
    let retry;

    function connect() {
        if (closed || !stream) return;
        ws = new WebSocket(`wss://stream.binance.com:9443/ws/${stream}`);
        ws.onmessage = (e) => {
            try {
                onMessage(JSON.parse(e.data));
            } catch {}
        };
        ws.onclose = () => {
            if (!closed) retry = setTimeout(connect, 3000);
        };
    }

    onMounted(connect);
    onBeforeUnmount(() => {
        closed = true;
        clearTimeout(retry);
        // closing a socket that is still connecting logs an error; close it once it opens
        if (ws && ws.readyState === WebSocket.CONNECTING) ws.onopen = () => ws.close();
        else ws && ws.close();
    });
}

export const binanceSymbol = (pairSymbol) => (pairSymbol || 'BTC_USDT').replace('_', '').toLowerCase();
