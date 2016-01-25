Summary: SOGo Thunderbird plugin for NethServer
Name: nethserver-sogo-thunderbird
Version: 1.1.2
Release: 1%{?dist}
License: GPL
URL: %{url_prefix}/%{name} 
Source0: %{name}-%{version}.tar.gz
BuildArch: noarch

Requires: nethserver-httpd
Requires: nethserver-php
Requires: zip, unzip, patch

BuildRequires: nethserver-devtools 

%description
SOGo Thunderbird plugin for NethServer

%prep
%setup

%build
perl createlinks

%install
rm -rf %{buildroot}
(cd root; find . -depth -print | cpio -dump %{buildroot})
%{genfilelist} %{buildroot} > %{name}-%{version}-filelist

%files -f %{name}-%{version}-filelist
%defattr(-,root,root)
%doc COPYING
%dir %{_nseventsdir}/%{name}-update

%changelog
* Tue Mar 10 2015 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 1.1.2-1
- Wrong UpdateURL into sogo-integrator.xpi if hostname or domain is changed - Bug #2899 [NethServer]

* Wed Feb 26 2014 Davide Principi <davide.principi@nethesis.it> - 1.1.1-1.ns6
- English version of TB plugin download page.

* Wed Feb 05 2014 Davide Principi <davide.principi@nethesis.it> - 1.1.0-1.ns6
- Rebuild for 6.5 beta3

* Tue Apr 30 2013 Davide Principi <davide.principi@nethesis.it> - 1.0.1-1.ns6
- Support Full automatic package install/upgrade/uninstall support #1870

* Tue Mar 19 2013 Giacomo Sanchietti <giacomo.sanchietti@nethesis.it> - 1.0.0-2.ns6
- Update URL tag Refs #1654
- Set minimum version requirements. #1653
 

* Fri Feb  8 2013 Davide Principi <davide.principi@nethesis.it> - 1.0.0-1.ns6
- Initial version




