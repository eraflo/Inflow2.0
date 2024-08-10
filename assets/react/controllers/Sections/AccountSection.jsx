import React from "react";
import TransparentButton from "../Buttons/TransparentButton";
import UILabelSemiBold from "../Texts/UILabelSemiBold";
import Image from "../Images/Image";
import LinkedImage from "../Images/LinkedImage";

export default class AccountSection extends React.Component {
    
        constructor(props) {
            super(props);
            this.state = {
                isLoggedIn: props.isLoggedIn,
                id : props.id
            };
        }
    
    
        render() {
            const isLoggedIn = this.state.isLoggedIn;
            const id = "/users/" + this.state.id;
            let accountSection;


            if (isLoggedIn) {
                accountSection =    (
                    <React.Fragment>
                        <LinkedImage 
                            src="/svg/user.svg"
                            alt='user'
                            width='1.5rem'
                            height='1.5rem'
                            margin='0.7rem 0'
                            link={id} 
                        />
                        <TransparentButton text="Se Déconnecter" link="/logout" />
                    </React.Fragment>
                )
            } else {
                accountSection =    (
                    <React.Fragment>
                        <a href="/login"><UILabelSemiBold text="Connexion" color="var(--light-grey)" /></a>
                        <TransparentButton text="S'inscrire" link="/inscription" />
                    </React.Fragment>
                )
            }

            return (
                <div className="account-section">
                    {accountSection}
                </div>
            );
        }
    }