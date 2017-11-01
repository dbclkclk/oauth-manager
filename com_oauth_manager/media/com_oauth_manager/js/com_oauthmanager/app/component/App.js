var React = require('react');
var CSSModules = require('react-css-modules');
var ReactRouter = require('react-router-dom');
var Router = ReactRouter.BrowserRouter;
var Route = ReactRouter.Route;
import styles from '../../../../sass/layout/_layout.scss';
var Index = require('../pages/Index');
var SideBar = require('../component/SideBar');

class App extends React.Component {

    render () {
        return (
            <Router>
                <div className="default-neat-grid ">
                    <div className="row-fluid">
                        <SideBar />
                        <div className="span10">
                            <Route path="/" component={Index} />
                        </div>
                    </div>
                </div>
            </Router>
        );
    };
}

export default CSSModules(App, styles);