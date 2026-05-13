describe('Register Page', function() {
    it('fails with invalid inputs', function() {
        cy.visit('/register')

        const user = {
            name: 'test',
            username: 'test',
            email: 'test',
            password: 'test123456'
        }

        cy.get('#name').type(user.name)
        cy.get('#username').type(user.name)
        cy.get('#email').type(user.name)
        cy.get('#password').type(user.password)
        cy.get('#password_confirmation').type(user.password + 'test')
        
        cy.get('button').click()

        cy.location('pathname').should('eq', '/register')

        cy.get('#password').invoke('text').should('eq', '')
        cy.get('#password_confirmation').invoke('text').should('eq', '')

        cy.get('#username').siblings('span.invalid-feedback').should('exist')
        cy.get('#username').siblings('span.invalid-feedback').contains('The username must be at least 8 characters.')
        
        cy.get('#email').siblings('span.invalid-feedback').should('exist')
        cy.get('#email').siblings('span.invalid-feedback').contains('The email must be a valid email address.')

        cy.get('#password').siblings('span.invalid-feedback').should('exist')
        cy.get('#password').siblings('span.invalid-feedback').contains('The password confirmation does not match.')

        cy.location('pathname').should('eq', '/register')
    })

    it('succeeds with valid inputs', function() {
        cy.visit('/register')

        cy.location('pathname').should('eq', '/register')

        const user = {
            name: 'test',
            username: 'test00001',
            email: 'test00001@example.com',
            password: 'test123456'
        }

        cy.get('#name').type(user.name)
        cy.get('#username').type(user.username)
        cy.get('#email').type(user.email)
        cy.get('#password').type(user.password)
        cy.get('#password_confirmation').type(user.password)
        
        cy.get('button').click()

        cy.location('pathname').should('eq', '/dashboard')

        cy.get('#navbarDropdown').contains('Welcome')
        cy.get('#navbarDropdown').contains(user.name)
    })

    it('fails with valid inputs but duplicate entry', function() {
        cy.visit('/register')

        cy.location('pathname').should('eq', '/register')

        const user = {
            name: 'test',
            username: 'test00001',
            email: 'test@example.com',
            password: 'test123456'
        }

        cy.get('#name').type(user.name)
        cy.get('#username').type(user.username)
        cy.get('#email').type(user.email)
        cy.get('#password').type(user.password)
        cy.get('#password_confirmation').type(user.password)
        
        cy.get('button').click()

        cy.location('pathname').should('eq', '/register')

        cy.get('#password').invoke('text').should('eq', '')
        cy.get('#password_confirmation').invoke('text').should('eq', '')

        cy.get('#name').siblings('span.invalid-feedback').should('exist')
        cy.get('#name').siblings('span.invalid-feedback').contains('The name has already been taken.')

        cy.get('#username').siblings('span.invalid-feedback').should('exist')
        cy.get('#username').siblings('span.invalid-feedback').contains('The username has already been taken.')
    })
})
