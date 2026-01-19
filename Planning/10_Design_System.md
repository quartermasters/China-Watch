# Design System: Cinematic Intelligence

## Philosophy
**"The Terminal for the Modern Age."**
A fusion of high-frequency trading terminals (Bloomberg) and next-gen cyber-intelligence dashboards. It feels "alive," "urgent," and "premium."

## 1. Color Palette (Dark Mode Only)
We use a "Void" base to make data pop.

*   **Backgrounds**:
    *   `--bg-void`: `#050505` (Main Background)
    *   `--bg-surface`: `#111111` (Card/Tile Background)
    *   `--bg-glass`: `rgba(17, 17, 17, 0.7)` (Overlays)
*   **Accents (The Signals)**:
    *   `--signal-red`: `#FF453A` (Volatility/Risk)
    *   `--signal-amber`: `#FFD60A` (Warning/Trend)
    *   `--signal-green`: `#32D74B` (Growth/Stability)
    *   `--signal-blue`: `#0A84FF` (Information/Neutral)
*   **Text**:
    *   `--text-primary`: `#FFFFFF`
    *   `--text-secondary`: `#8E8E93`
    *   `--text-muted`: `#48484A`

## 2. Typography
*   **Labels & UI**: `Inter` (Google Fonts) or System Sans. Clean, legible, invisible.
*   **Data & Tickers**: `JetBrains Mono` or `SF Mono`. Technical, tabular figures (monospaced numbers) are crucial for financial data.

## 3. Layout: The Bento Grid
We use CSS Grid to create a modular "Dashboard" feel.
*   **Container**: `display: grid; gap: 1rem;`
*   **Tiles**: Each component (Chart, Map, Ticker) lives in a rounded tile.
    *   `border-radius: 12px`
    *   `border: 1px solid rgba(255, 255, 255, 0.1)`

## 4. Visual Effects
*   **Glassmorphism**: Sticky headers and popovers use `backdrop-filter: blur(12px)`.
*   **Glow**: Active data points have a subtle box-shadow glow.
    *   `box-shadow: 0 0 10px rgba(255, 69, 58, 0.3)`
*   **Micro-Interactions**:
    *   Hovering a tile slightly scales it (`transform: scale(1.01)`).
    *   Buttons have a "press" effect.

## 5. Mobile Adaptation
*   **Stacked Bento**: On mobile, the grid collapses to a single column, but "Key Indicators" (The Ticker) allow horizontal swiping to save vertical space.
