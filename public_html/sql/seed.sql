-- Sample data for local development/testing only. Never run against production —
-- real listings are entered through the admin panel (Phase 5).

INSERT INTO projects (title, type, location, price, size, status, rera_number, short_desc, full_desc, featured) VALUES
('Roop Shree Residency', 'Flat', 'Shastri Nagar, Jodhpur', 'Rs 32 Lakh onwards', '1050-1450 sq. ft.', 'Available', 'RAJ/P/2024/1234',
 '2 & 3 BHK flats with modern amenities in a prime location.',
 'Roop Shree Residency offers thoughtfully designed 2 and 3 BHK apartments close to schools, hospitals, and markets in Shastri Nagar. Each unit features ample natural light, quality fittings, and dedicated parking. The project includes a landscaped common area and 24/7 security.',
 TRUE),

('Green Valley Plots', 'Plot', 'Pal Road, Jodhpur', 'Rs 12 Lakh onwards', '1200-2400 sq. yard', 'Available', 'RAJ/P/2024/5678',
 'RERA-approved residential plots ready for construction.',
 'Green Valley Plots is a gated residential plotted development on Pal Road with wide internal roads, underground electrical wiring, and dedicated green belts. Plots are available in multiple sizes, fully approved and ready for immediate construction.',
 TRUE),

('Shree Vilas', 'Villa', 'Ratanada, Jodhpur', 'Rs 85 Lakh onwards', '2800 sq. ft.', 'Coming Soon', NULL,
 'Premium independent villas with private gardens.',
 'Shree Vilas brings a limited collection of independent villas to Ratanada, each with a private garden, covered parking for two vehicles, and a rooftop terrace. Bookings open soon — register your interest for early access.',
 TRUE),

('Roop Shree Business Hub', 'Commercial', 'Jodhpur-Pali Road', 'Price on request', '500-5000 sq. ft.', 'Available', 'RAJ/P/2023/9999',
 'Commercial spaces suited for retail and office use.',
 'Roop Shree Business Hub offers flexible commercial units along the high-visibility Jodhpur-Pali Road corridor, suitable for retail showrooms, offices, and clinics. Units can be combined for larger requirements.',
 FALSE),

('Sunrise Enclave', 'Flat', 'Chopasni Housing Board, Jodhpur', 'Rs 28 Lakh onwards', '900-1200 sq. ft.', 'Sold', NULL,
 'A fully sold-out residential project delivered on schedule.',
 'Sunrise Enclave was a residential project in Chopasni Housing Board, fully delivered and now completely sold out — shown here as a track record of on-time delivery.',
 FALSE),

('Heritage Homes', 'Villa', 'Mandore, Jodhpur', 'Rs 1.1 Cr onwards', '3200 sq. ft.', 'Available', 'RAJ/P/2024/4321',
 'Spacious heritage-style villas near Mandore Gardens.',
 'Heritage Homes combines traditional Rajasthani architectural detailing with modern interiors, located minutes from Mandore Gardens. Each villa includes a courtyard, four bedrooms, and a dedicated staff room.',
 TRUE);
