describe('Home Page', function() {
    it('successfully loads', function() {
        cy.visit('/')

        cy.location('pathname').should('eq', '/home')
    })
})

describe('Dashboard Page', function() {
    it('successfully redirects to login page when not unauthenticated', function() {
        cy.visit('/dashboard')

        cy.location('pathname').should('eq', '/dashboard')
        
        cy.get('.nav-link').should('exist')
        cy.get('.nav-link').invoke('text').should('eq', 'Login')
    })
})

describe('Body Scan Page', function() {
    it('successfully redirects to login page when not unauthenticated', function() {
        cy.visit('/bodyscan')

        cy.location('pathname').should('eq', '/login')
    })
})

describe('Body Scan Info Page', function() {
    it('successfully loads', function() {
        cy.visit('/bodyscan/info')

        cy.location('pathname').should('eq', '/bodyscan/info')
    })
})

describe('Chakra Scan Page', function() {
    it('successfully redirects to login page when not unauthenticated', function() {
        cy.visit('/chakrascan')

        cy.location('pathname').should('eq', '/login')
    })
})

describe('Chakra Scan Info Page', function() {
    it('successfully loads', function() {
        cy.visit('/chakrascan/info')

        cy.location('pathname').should('eq', '/chakrascan/info')
    })
})

describe('Data Cache Page', function() {
    it('successfully redirects to login page when not unauthenticated', function() {
        cy.visit('/data_cache')

        cy.location('pathname').should('eq', '/login')
    })
})

describe('Data Cache Info Page', function() {
    it('successfully loads', function() {
        cy.visit('/data_cache/info')

        cy.location('pathname').should('eq', '/data_cache/info')

        cy.get('.nav-link').should('exist')
        cy.get('.nav-link').invoke('text').should('eq', 'Login')
    })
})

describe('BioConnect Page', function() {
    it('successfully loads', function() {
        cy.visit('/bioconnect')

        cy.location('pathname')
        .should('eq', '/bioconnect')

        cy.get('.nav-link').should('exist')
        cy.get('.nav-link').invoke('text').should('eq', 'Login')
    })
})

describe('BioConnect Discussion Page', function() {
    it('successfully loads', function() {
        cy.visit('/bioconnect/groups')

        cy.location('pathname').should('eq', '/bioconnect/groups')

        cy.get('.nav-link').should('exist')
        cy.get('.nav-link').invoke('text').should('eq', 'Login')
    })
})

describe('More Page', function() {
    it('successfully loads', function() {
        cy.visit('/products')

        cy.location('pathname').should('eq', '/products/bio')

        cy.get('.nav-link').should('exist')
        cy.get('.nav-link').invoke('text').should('eq', 'Login')

        cy.get('input[name="quantity"]').should('be.disabled')
        cy.get('input[name="quantity"]').invoke('val').should('eq', '1')
        cy.get('input[name="product_id"]').should('be.disabled')
        cy.get('input[name="product_id"]').invoke('val').should('eq', '1')
    })
})
