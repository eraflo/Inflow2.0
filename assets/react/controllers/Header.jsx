import React from 'react';

import LinkedImage from './Images/LinkedImage.jsx';
import AccountSection from './Sections/AccountSection.jsx';
import NavLink from './NavLink.jsx';

export default class Header extends React.Component {

    constructor(props) {
        super(props);
        this.state = {
            isLoggedIn: props.isLoggedIn,
            isAdmin: props.isAdmin,
            id: props.id
        };
    }


    render() {
        const isLoggedIn = this.state.isLoggedIn;
        const isAdmin = this.state.isAdmin;
        let accountSection;
        let adminLink;

        accountSection = <AccountSection isLoggedIn={isLoggedIn} id={this.state.id} />;

        if (isAdmin) {
            adminLink = <a href="#admin">Admin</a>;
        }

        return (
            <header className="container-lg">
                <nav className="nav nav-pills flex-column flex-sm-row">
                    <div className='nav-logo'>
                        <LinkedImage 
                            src='/img/plain/logo_no_background.png' 
                            alt='logo'
                            width='6rem'
                            height='auto'
                            margin='0'
                            link='/' 
                        />
                    </div>
                    <div className="nav-links">
                        <NavLink 
                            link="/" 
                            text="Accueil" 
                            color="var(--light-grey)"
                            isGradient={false}
                        />
                        <NavLink 
                            link="/playlists" 
                            text="Musiques" 
                            color="var(--light-grey)"
                            isGradient={false}
                        />
                        <NavLink 
                            link="/videos/youtube/1" 
                            text="Vidéos" 
                            color="var(--light-grey)"
                            isGradient={false}
                        />
                        {adminLink}
                    </div>
                    {accountSection}
                </nav>
            </header>
        );
    }
}