# Project Memory — Jabulani Store

This directory serves as the **Long-Term Memory** for this Laravel eCommerce project. It is designed to be read by AI agents and developers to understand the system's architecture, state, and recent changes.

## 📌 Context Index
- **[SYSTEM_OVERVIEW.md](file:///d:/Faizi%20related%20Data/JabulaniStore-Antigravity/jabulani-system/docs/memory/SYSTEM_OVERVIEW.md)**: Goals/Modules.
- **[DATABASE_SCHEMA.md](file:///d:/Faizi%20related%20Data/JabulaniStore-Antigravity/jabulani-system/docs/memory/DATABASE_SCHEMA.md)**: Master Table/Relationship map.
- **[PRODUCT_FLOW.md](file:///d:/Faizi%20related%20Data/JabulaniStore-Antigravity/jabulani-system/docs/memory/PRODUCT_FLOW.md)**: Life cycle of a Product/Inventory.
- **[ORDER_FLOW.md](file:///d:/Faizi%20related%20Data/JabulaniStore-Antigravity/jabulani-system/docs/memory/ORDER_FLOW.md)**: Life cycle of a Cart/Checkout.
- **[ADMIN_PANEL.md](file:///d:/Faizi%20related%20Data/JabulaniStore-Antigravity/jabulani-system/docs/memory/ADMIN_PANEL.md)**: Administrative Capabilities/Controllers.
- **[ARCHITECTURE.md](file:///d:/Faizi%20related%20Data/JabulaniStore-Antigravity/jabulani-system/docs/memory/ARCHITECTURE.md)**: Tech Stack (MVC/Laravel/Frontend).
- **[IMPROVEMENT_PLAN.md](file:///d:/Faizi%20related%20Data/JabulaniStore-Antigravity/jabulani-system/docs/memory/IMPROVEMENT_PLAN.md)**: Tech Debt/Roadmap.
- **[CHANGELOG.md](file:///d:/Faizi%20related%20Data/JabulaniStore-Antigravity/jabulani-system/docs/memory/CHANGELOG.md)**: History of major edits.

---

## 🤖 Instructions for AI Agents (MUST READ)

The following rules ensure this project's documentation remains an accurate "Single Source of Truth":

1. **Self-Documentation Requirement**: Upon completing ANY architectural, database, or core feature change (Controllers/Routes), you MUST update the corresponding `.md` file in `docs/memory/`.
2. **Maintenance of the Changelog**: Every completed request MUST have a brief entry in `docs/memory/CHANGELOG.md` identifying the date, summary of changes, and any fixed bugs.
3. **Index Management**: If you create a new module, add it to `SYSTEM_OVERVIEW.md` and link its specific flow (if any) to this index.
4. **Token Saving**: You are encouraged to reference these memory files instead of re-scanning all migrations/controllers to save time and reduce token consumption.

> [!IMPORTANT]
> A project without up-to-date memory leads to architectural drift. Before starting a large refactor, always cross-reference `DATABASE_SCHEMA.md` and `ARCHITECTURE.md`.
