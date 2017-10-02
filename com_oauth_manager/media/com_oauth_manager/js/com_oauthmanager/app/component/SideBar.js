var React = require('react');
var ReactRouter = require('react-router-dom');
var NavLink = ReactRouter.NavLink;


class SideBar extends React.Component {
    render () {
        return (
            <aside className="sidebar-left">
                    <a className="company-logo" href="#">Logo</a>
                    <div className="sidebar-links">
                        <NavLink className="link-blue" to="/"><i className="fa fa-picture-o"></i>Home</NavLink>
                        <NavLink className="link-red" to="/"><i className="fa fa-heart-o"></i>Favorites</NavLink>
                        <NavLink className="link-yellow selected" to="/"><i className="fa fa-keyboard-o"></i>Projects</NavLink>
                        <NavLink className="link-green" to="/"><i className="fa fa-map-marker"></i>Places</NavLink>
                    </div>

            </aside>
        )
    }
}

module.exports = SideBar;