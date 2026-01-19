# China Watch - TODO & Roadmap

## Current Status: MVP Complete ✅

The core application is functional with all major features implemented:
- RSS ingestion and event clustering
- Live feed with Bloomberg-inspired UI
- Rumor tracking and data visualization
- Search, subscriptions, and SEO optimization

---

## High Priority (Next 2 weeks)

### Performance & Reliability
- [ ] **Implement proper error boundaries** in JavaScript for graceful failure handling
- [ ] **Add database connection pooling** to handle concurrent requests
- [ ] **Optimize SQL queries** - add EXPLAIN analysis for slow queries
- [ ] **Implement Redis caching** to replace file-based cache for better performance
- [ ] **Add health check endpoint** (`/health`) for monitoring systems
- [ ] **Set up proper log rotation** to prevent disk space issues

### Content Quality
- [ ] **Implement basic AI summarization** using OpenAI API (when AI_MODE=api)
- [ ] **Add duplicate detection** beyond URL matching (content similarity)
- [ ] **Improve entity extraction** with better regex patterns for Chinese companies/places
- [ ] **Add confidence decay** for older unverified rumors
- [ ] **Implement source reliability tracking** based on historical accuracy

### User Experience
- [ ] **Add infinite scroll loading states** with skeleton UI
- [ ] **Implement client-side filters** for instant topic/source filtering
- [ ] **Add search autocomplete** with popular terms and entities
- [ ] **Create mobile-optimized navigation** with hamburger menu
- [ ] **Add dark/light theme toggle** (current is dark-only)

---

## Medium Priority (Next month)

### Data & Analytics
- [ ] **Expand economic indicators** - add more metrics from PBOC, NDRC
- [ ] **Implement trending algorithms** beyond simple counts
- [ ] **Add geographic clustering** for province-level news tracking
- [ ] **Create data export APIs** for researchers and analysts
- [ ] **Build basic analytics dashboard** for admin use

### Content Features  
- [ ] **Add newsletter HTML templates** with better formatting
- [ ] **Implement breaking news alerts** via email for major events
- [ ] **Add event timeline visualization** with interactive charts
- [ ] **Create topic-specific RSS feeds** for focused consumption
- [ ] **Add social media sharing optimization** with better meta tags

### Technical Improvements
- [ ] **Implement proper unit tests** for critical functions
- [ ] **Add Docker containerization** for easier deployment
- [ ] **Create backup/restore scripts** for database and storage
- [ ] **Add rate limiting** for API endpoints
- [ ] **Implement CSRF protection** for forms

---

## Low Priority (Future releases)

### Advanced Features
- [ ] **User accounts and personalization** (watch lists, custom topics)
- [ ] **Comment system** with moderation for articles
- [ ] **API authentication** for external developers
- [ ] **Webhook system** for real-time notifications
- [ ] **Multi-language support** (Chinese simplified/traditional)

### AI & Machine Learning
- [ ] **Sentiment analysis** for news items
- [ ] **Automatic fact-checking** against known reliable sources
- [ ] **Predictive analytics** for emerging trends
- [ ] **Content recommendation engine** based on reading patterns
- [ ] **Automated translation** for Chinese-language sources

### Business Features
- [ ] **Premium subscription tiers** with advanced features
- [ ] **Advertiser dashboard** for ad management
- [ ] **Analytics for publishers** showing referral traffic
- [ ] **White-label solutions** for other organizations
- [ ] **API monetization** with usage-based pricing

---

## Technical Debt & Maintenance

### Code Quality
- [ ] **Refactor large controller methods** into smaller, focused methods
- [ ] **Add type hints** to all function parameters and returns
- [ ] **Implement proper exception handling** with custom exception classes
- [ ] **Add code documentation** with PHPDoc comments
- [ ] **Create API documentation** with OpenAPI/Swagger

### Security
- [ ] **Implement Content Security Policy (CSP)** headers
- [ ] **Add input validation middleware** for all endpoints
- [ ] **Audit third-party dependencies** for vulnerabilities
- [ ] **Implement proper session management** if user accounts added
- [ ] **Add honeypot fields** to prevent bot submissions (partially done)

### Infrastructure
- [ ] **Set up staging environment** matching production
- [ ] **Create automated deployment pipeline** with testing
- [ ] **Implement database migration system** with version control
- [ ] **Add monitoring dashboards** with Grafana/similar
- [ ] **Create disaster recovery plan** and test procedures

---

## Known Issues

### Critical
- None currently identified

### Non-Critical
- [ ] **Chart.js loading delay** on slow connections - add loading states
- [ ] **Long headlines truncation** in mobile view needs refinement  
- [ ] **Search highlighting** doesn't handle partial word matches well
- [ ] **Timezone handling** assumes UTC - should detect user timezone
- [ ] **RSS parsing errors** for malformed XML should be more graceful

### Browser Compatibility
- [ ] **Test on Safari iOS** - some CSS Grid properties might need prefixes
- [ ] **Internet Explorer 11** support if required (currently not supported)
- [ ] **High contrast mode** accessibility for Windows users

---

## Performance Benchmarks

### Current Targets (to maintain)
- Page load time: <1.8s on 4G mid-range phones ✅
- API response time: <300ms for cached endpoints ✅  
- RSS ingestion: <45s runtime, <40 items per run ✅
- Search response: <1s for full-text queries ✅
- Database queries: <100ms for complex joins ✅

### Stretch Goals
- Page load time: <1.0s on 4G
- API response time: <150ms average
- RSS ingestion: <30s runtime
- Search response: <500ms
- Support for 1000+ concurrent users

---

## Analytics & Metrics to Track

### Content Quality
- Source diversity (% from different types)
- Rumor accuracy rate (verified vs retracted)
- Event clustering precision (manual spot-checks)
- User engagement (time on site, pages per session)

### Technical Performance  
- Server response times by endpoint
- Error rates and types
- Cache hit/miss ratios
- Database query performance

### Business Metrics
- Email subscription conversion rate
- Daily/monthly active users
- Newsletter open/click rates
- Search query patterns

---

## Potential Integrations

### Data Sources
- [ ] **Financial data APIs** (stock prices for Chinese companies)
- [ ] **Weather APIs** for natural disaster impact analysis
- [ ] **Social media APIs** (Twitter, LinkedIn) for trending topics
- [ ] **Government data APIs** beyond RSS feeds
- [ ] **Academic research databases** for think tank content

### External Services
- [ ] **Google Analytics 4** integration for detailed user tracking
- [ ] **Mailchimp/SendGrid** for professional email marketing
- [ ] **Slack/Discord** webhooks for team notifications
- [ ] **Zapier** integration for workflow automation
- [ ] **CloudFlare** for CDN and DDoS protection

---

## Research & Validation Needed

### User Research
- [ ] **Survey existing users** about most valuable features
- [ ] **A/B testing** for newsletter signup conversion
- [ ] **Usability testing** on mobile devices
- [ ] **Interview China researchers** about workflow needs

### Technical Research
- [ ] **Evaluate other news aggregation platforms** for feature ideas
- [ ] **Research China-specific NLP libraries** for better text processing
- [ ] **Study financial data visualization** best practices
- [ ] **Investigate real-time streaming** alternatives to polling

### Competitive Analysis
- [ ] **Bloomberg Terminal** feature comparison and gaps
- [ ] **Reuters** China coverage approach
- [ ] **South China Morning Post** digital strategy
- [ ] **Caixin** subscription model and pricing

---

## Dependencies & Risks

### Critical Dependencies
- **RSS feed availability** - need backup plans for source changes
- **Database stability** - MySQL performance under load
- **Hosting reliability** - need uptime SLA and monitoring
- **External APIs** - OpenAI, Google Analytics rate limits

### Risk Mitigation
- [ ] **Multiple RSS sources** per topic to prevent single points of failure
- [ ] **Database replication** for high availability
- [ ] **CDN implementation** for static asset delivery
- [ ] **API key rotation** and fallback strategies

---

## Notes

- This TODO list should be reviewed and updated weekly
- Priority levels may change based on user feedback and business needs
- All performance targets should be measured and tracked continuously
- Security items should be addressed before any public launch
- Consider breaking down large tasks into smaller, manageable chunks

**Last Updated**: December 2024  
**Next Review**: Weekly during active development
