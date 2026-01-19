# Domain Modules

## 1. Economic Monitor ("Hard Pulse")
Focuses on physical and market indicators.
-   **PortModule**:
    -   *Source*: Shanghai International Port Group (SIPG) daily reports.
    -   *Metric*: TEU (Twenty-foot Equivalent Unit) throughput.
-   **CommodityModule**:
    -   *Source*: 100ppi.com (Commodity Data).
    -   *Metric*: Daily spot price variance.

## 2. Political Monitor ("Ritual Engine")
Focuses on language and personnel.
-   **RhetoricModule**:
    -   *Input*: People's Daily, Qiushi, PLA Daily.
    -   *Processing*: Tokenization -> Stopword Removal -> TF-IDF -> Anomaly Detection.
-   **PersonnelModule**:
    -   *Input*: Gov.cn leadership subpages.
    -   *Processing*: DOM Diffing.

## 3. Global Proxy Monitor ("The Map")
Focuses on third-country influence.
-   **TenderModule**:
    -   *Input*: ADB, World Bank, African Development Bank tender sites.
    -   *Pattern Matching*: Regex for known SOE names (CCCC, CRCC, PowerChina).
-   **DiplomacyModule**:
    -   *Input*: MFA sites of "Swing States" (Indonesia, Brazil, KSA).
    -   *Classification*: "Visit", "Agreement", "Statement".
